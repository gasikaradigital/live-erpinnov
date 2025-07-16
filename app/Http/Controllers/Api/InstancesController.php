<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ReplaceFreeSubdomain;
use App\Models\Entreprise;
use App\Models\Instance;
use App\Models\InstanceQuota;
use App\Models\Subscription;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\FastInstanceProvisioningService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\instance;
use function PHPSTORM_META\type;

class InstancesController extends Controller
{

    /**
     * Recuperation des utilisateurs
     */
    public function getInstanceByUser(Request $request)
    {

        /**
         *  @var User $user
         */
        $user  = auth()->user();

        /**
         * @var array $instances
         */
        $instances = Instance::where('user_id', $user->id)
            ->with('subscription.plan')
            ->latest()
            ->get();

        return response()->json($instances, '200');
    }

    /**
     * Creation de l'instance
     */
    public function createInstance(Request $request)
    {

        /**
         *  @var User $user
         */
        $user = auth()->user();


/*        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'unique:instances,name',
                'min:3',
                'max:15',
                'regex:/^[a-zA-Z0-9_-]*$/'
            ],
            'enterpriseId' => ['integer', 'required'],
            //'planId' => ['integer', 'required'],
            'source' => ['string', 'required']
        ]); */

        return $response->json([
            'message' => "api pointé",
        ], 201);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }
        /**
         * Nom de l'instance
         * 
         * @var string $instanceName
         */
        $instanceName = $request->name;

        /**
         * @var int $planId
         */
        $planId = (int) $request->planId;

        /**
         * @var int $enterpriseId
         */
        $entrepriseId = (int) $request->enterpriseId;

        /**
         * @var string $source
         */
        $source = $request->source;

        try {
            DB::beginTransaction();

            if(!$user->profile->isComplete()){
                return response()->json(['message'=>"Le profile de l'utilisateur doit etre complet",'type'=>'UNCLOMPLETED_PROFILE'],401);
            }

            if (!$this->isInstanceNameUnique($instanceName)) {
                return response()->json(['message' => 'Le nom de l\instace est deja pris', 'suggestion' => ''], 400);
            }

            /**
             * @var Subscription|null $subscription
             */
            $subscription = $this->selectedSubscription($planId, $user->id);

            if ($subscription === null) {
                DB::rollBack();
                return response()->json(['message' => 'L\'utilisateur n\'a pas d\'abonnement sur cette plan'], 403);
            }

            /**
             * Entreprise selectionné
             * 
             * @var Enterprise|null $enterprise
             */
            $enterprise = Entreprise::find($enterpriseId);

            if ($enterprise === null) {
                return response()->json(['message' => 'L\'entreprise selectionné n\'existe pas'], 400);
            }

            /**
             * @var array|null $instanceData
             */
            $instanceData = $this->createInstaceData($instanceName);
            if ($instanceData === null) {
                return response()->json(['message' => 'Impossible de recuperer un instance libre'], 500);
            }

            $instance = Instance::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'reference' => Instance::generateNextReference(),
                'name' => $instanceName,
                'entreprise_id' => $enterprise->id,
                'status' => 'active',
                'url' => $instanceName . '.erpinnov.com',
                'auth_token' => Instance::generateUniqueAuthToken(),
                'dolibarr_username' => $instanceData['login_dolibarr'],
                'dolibarr_password' => Hash::make($instanceData['password_dolibarr']),
                'dolibarr_api_key' => $instanceData['api_key_dolibarr'],
                'pays' => $enterprise->pays === 'Madagascar' ? 0 : 1,
            ]);

            $fastProvisioning = new FastInstanceProvisioningService();
            $success = $fastProvisioning->createInstance($instanceData, $user, $instance, $source, $entrepriseId);

            if (!$success) {
                throw new Exception('Échec du provisionnement de l\'instance.');
            }


            DB::commit();

            try{
                (new ReplaceFreeSubdomain())->handle();
            } catch(\Exception $e){
                dd($e->getMessage());
            }

            return response()->json([
                'id' => $instance->id,
                'name' => $instance->name,
                'login' => $user->email,
                'password' => $instanceData['password_dolibarr'],
                'url' => "http://" . $instance->name . ".erpinnov.com",
                'created_at' => now(),
                'created_by' => $user->email
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'name' => $instanceName ?? null
            ], 500);
        }
    }

    private function isInstanceNameUnique(string $instanceName): bool
    {
        $instanceCount = Instance::where('name', $instanceName)->count();
        if ($instanceCount != 0) {
            return false;
        }
        return true;
    }

    private function selectedSubscription(int $selectedPlanId, int $userId): ?Subscription
    {

        return Subscription::where('user_id', $userId)
            ->where('plan_id', $selectedPlanId)
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
            ->latest()
            ->first();
    }

    private function createInstaceData($instanceName): ?array
    {
        // Recuperation de l'instance libre
        try {

            $instance_free = InstanceQuota::where('statut', 'libre')->first();
            $dolibarrPassword = $instance_free->password;
            return  [
                'name' => $instanceName,
                'password_dolibarr' => $dolibarrPassword,
                'login_dolibarr' => 'admin',
                'url_suffix' => Str::slug($instanceName),
                'api_key_dolibarr' => $instance_free->api_key,
            ];
        } catch (Exception $e) {
            logger('no free instance');
            return null;
        }
    }
}
