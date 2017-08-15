<?php

namespace App\Http\Controllers;

use View;
use Option;
use App\Models\User;
use App\Models\Closet;
use App\Models\Texture;
use App\Models\ClosetModel;
use Illuminate\Http\Request;
use App\Exceptions\PrettyPageException;

class ClosetController extends Controller
{
    /**
     * Instance of Closet.
     *
     * @var \App\Models\Closet
     */
    private $closet;

    public function __construct()
    {
        $this->closet = new Closet(session('uid'));
    }

    public function index()
    {
        return view('user.closet')->with('user', app('user.current'));
    }

    public function getClosetData(Request $request)
    {
        $category = $request->input('category', 'skin');
        $page     = abs($request->input('page', 1));
        $q        = $request->input('q', null);
        $width    = $request->input('width', null);

        $items = [];

        if ($q) {
            // do search
            foreach ($this->closet->getItems($category) as $item) {
                if (stristr($item->name, $q)) {
                    $items[] = $item;
                }
            }
        } else {
            $items = $this->closet->getItems($category);
        }

        // pagination
        $row = $width == 1080 ? 8 : 6;
        
        $total_pages = ceil(count($items) / $row);

        $items = array_slice($items, ($page - 1) * $row, $row);

        return response()->json([
            'category'    => $category,
            'items'       => $items,
            'total_pages' => $total_pages
        ]);
    }

    public function info()
    {
        return json($this->closet->getItems());
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'tid'  => 'required|integer',
            'name' => 'required|no_special_chars'
        ]);

        if (app('user.current')->getScore() < option('score_per_closet_item')) {
            return json(trans('user.closet.add.lack-score'), 7);
        }

        if ($this->closet->add($request->tid, $request->name)) {
            $t = Texture::find($request->tid);
            $t->likes += 1;
            $t->save();

            app('user.current')->setScore(option('score_per_closet_item'), 'minus');

            return json(trans('user.closet.add.success', ['name' => $request->input('name')]), 0);
        } else {
            return json(trans('user.closet.add.repeated'), 1);
        }
    }

    public function rename(Request $request)
    {
        $this->validate($request, [
            'tid' => 'required|integer',
            'new_name' => 'required|no_special_chars'
        ]);

        if ($this->closet->rename($request->tid, $request->new_name)) {
            return json(trans('user.closet.rename.success', ['name' => $request->new_name]), 0);
        } else {
            return json(trans('user.closet.remove.non-existent'), 0);
        }
    }

    public function remove(Request $request)
    {
        $this->validate($request, [
            'tid'  => 'required|integer'
        ]);

        if ($this->closet->remove($request->tid)) {
            $t = Texture::find($request->tid);
            $t->likes = $t->likes - 1;
            $t->save();

            if (option('return_score'))
                app('user.current')->setScore(option('score_per_closet_item'), 'plus');

            return json(trans('user.closet.remove.success'), 0);
        } else {
            return json(trans('user.closet.remove.non-existent'), 0);
        }
    }

}
