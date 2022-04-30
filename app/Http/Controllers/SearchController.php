<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $search = $request->search;

            if (!empty($search) && strlen($search) > 2) {
                $items = DB::table('food')
                    ->join('categories', 'categories.id', '=', 'food.category_id')
                    ->select('food.name', 'food.description', 'food.price', 'categories.name as category_name')
                    ->where('food.name', 'LIKE', '%'.$search."%")
                    ->orderByDesc('categories.priority')
                    ->orderBy('categories.position')
                    ->get();

                if ($items) {
                    foreach ($items as $key => $item) {
                        $output .= '<tr>'.
                            '<td>'.($key + 1).'</td>'.
                            '<td>'.$item->name.'</td>'.
                            '<td>'.$item->description.'</td>'.
                            '<td>'.$item->category_name.'</td>'.
                            '<td> BHD '.$item->price.'</td>'.
                            '</tr>';
                    }
                    return Response($output);
                }
            }
        }
    }
}
