<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\View\View;

class UserController extends Controller
{
    public function bad(): View
    {
        $users = User::query()
            ->where(function ($query) {
                $texts = request('search');
                collect(explode(' ', $texts))->filter()->each(function ($text) use ($query) {
                    $search = "%$text%";
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search)
                            ->orWhereHas('company', function ($query) use ($search) {
                                $query->where('name', 'like', $search);
                            });
                    });

                });
            }
            )
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    public function better(): View
    {
        $users = User::query()
            ->with('company')
            ->where(function ($query) {
                $texts = request('search');
                collect(explode(' ', $texts))->filter()->each(function ($text) use ($query) {
                    $search = "$text%";
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search)
                            ->orWhereIn('company_id', function ($query) use ($search) {
                                $query->select('id')->from('companies')
                                    ->where('name', 'like', $search);
                            });
                    });
                });
            }
            )
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    /** separate queries on each searched term */
    public function good(): View
    {
        $users = User::query()
            ->with('company')
            ->where(function ($query) {
                $texts = request('search');
                collect(explode(' ', $texts))->filter()->each(function ($text) use ($query) {
                    $search = "$text%";
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search)
                            ->orWhereIn('company_id', Company::query()
                                ->where('name', 'like', $search)
                                ->pluck('id')
                            );
                    });
                });
            }
            )
            ->paginate(50);

        return view('users-list', ['users' => $users]);
    }

    public function best(): View
    {
        $users = User::query()
            ->select('id', 'first_name', 'last_name', 'company_id')
            ->with('company:id,name')
            ->where(function ($query) {
                $texts = request('search');
                collect(explode(' ', $texts))->filter()->each(function ($text) use ($query) {
                    $search = "$text%";
                    $query->whereIn('id', function ($query) use ($search) {
                        $query->select('id')->from(function ($query) use ($search) {
                            $query->select('id')
                                ->from('users')
                                ->where('first_name', 'like', $search)
                                ->orWhere('last_name', 'like', $search)
                                ->union($query->newQuery()
                                    ->select('users.id')
                                    ->from('users')
                                    ->join('companies', 'users.company_id', '=', 'companies.id')
                                    ->where('companies.name', 'like', $search));
                        }, 'matches');
                    });
                });
            }
            )
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
