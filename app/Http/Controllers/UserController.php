<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function bad(): View
    {
        $users = User::query()
            ->withBad(request('search'))
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    public function better(): View
    {
        $users = User::query()
            ->with('company')
            ->withBetter(request('search'))
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    /** separate queries on each searched term */
    public function good(): View
    {
        $users = User::query()
            ->with('company')
            ->withGood(request('search'))
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    public function best(): View
    {
        $users = User::query()
            ->select('id', 'first_name', 'last_name', 'company_id')
            ->with('company:id,name')
            ->withBest(request('search'))
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    public function statusBad(): View
    {
        $statuses = [
            'online' => User::query()->where('status', 'online')->count(),
            'offline' => User::query()->where('status', 'offline')->count(),
            'banned' => User::query()->where('status', 'banned')->count(),
        ];

        return view('users-status', ['status' => $statuses]);
    }

    public function statusGood(): View
    {
        /*
          By calling toBase function it will retrieve the data from the database, but it will not prepare the Eloquent models
          but will just give us raw data and help us to save memory.
         */
        $query = User::toBase()
            ->selectRaw('count(case when status = "online" then 1 end) as online')
            ->selectRaw('count(case when status = "offline" then 1 end) as offline')
            ->selectRaw('count(case when status = "banned" then 1 end) as banned')
            ->first();

        $statuses = [
            'online' => $query->online,
            'offline' => $query->offline,
            'banned' => $query->banned
        ];

        return view('users-status', ['status' => $statuses]);
    }
}
