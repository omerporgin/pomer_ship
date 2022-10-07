<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules;
use App\Http\Requests\UserRequest;

class RegisteredUserController extends Controller
{

    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return response()->view(theme('dashboard_app'), [
            'state_list' => $list['list'],
            'langsAll' => langsAll(),
            'user' => Auth::user(),
            'lang' => 109
        ]);
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create($lang = 109)
    {
        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return view('app.auth.register', [
            'state_list' => $list['list'],
            'lang' => $lang,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        $userData = $this->service->filteredRequest($request);

        $userData['password'] = Hash::make($request->password);

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $user = Auth::user();
        $placeHolderPassword = 'aVsd,Adas1d_asdasd'; // This is a valid password, which can pass validation
        $updatePassword = false;
        if (isset($request->password)) {
            $placeHolderPassword = $request->password;
            $updatePassword = true;
        }

        $placeHolderData = [
            'password' => $placeHolderPassword,
            'password_confirmation' => $placeHolderPassword,
        ];
        $request->request->add($placeHolderData);

        $data = $this->service->filteredRequest($request);

        $request->request->add([
            'warehouse_phone' => preg_replace('/\D/', '', $request->warehouse_phone),
            'invoice_phone' => preg_replace('/\D/', '', $request->invoice_phone)
        ]);

        // Remove placeholders
        foreach ($placeHolderData as $key => $item) {
            unset($data[$key]);
        }

        $user->update($data);

        if ($updatePassword) {
            $user->password = Hash::make($placeHolderPassword);
            $user->save();
        }

        return redirect()->route('app.dashboard')->with('is_updated', true);

    }

    /**
     * @return void
     */
    public function uploadImage(Request $request)
    {
        try {
            $uploadedFile = $request->file('avatar');
            $service = service('User');
            $service->saveImage($uploadedFile, 'user.' . Auth::id());
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'message' => $e->getMessage()
            ]);
        }
    }
}
