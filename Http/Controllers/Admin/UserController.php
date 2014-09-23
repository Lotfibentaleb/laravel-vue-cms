<?php namespace Modules\User\Http\Controllers\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class UserController extends AdminBaseController
{
    /**
     * @var \Modules\Session\Entities\User
     */
    protected $users;

    public function __construct()
    {
        parent::__construct();

        $this->users = Sentinel::getUserRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->users->createModel()->all();

        return View::make('user::admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('user::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return \View::make('collection.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!$user = $this->users->createModel()->find($id)) {
            return Redirect::route('dashboard.user.index');
        }

        return View::make('user::admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}