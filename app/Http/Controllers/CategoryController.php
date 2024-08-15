<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());
        return Inertia::render('Categories', ['categories' => $root['root'][0]['children']]);
    }
    public function tree($rootLabel = 'root', $parentId = null){
        $root = Category::where('label', 'root')->whereNull('parent_id')->with('children')->first();
        $root = $this->convertCategoriesForTreeSelect($root->toArray());
        return response()->json( $root[0]['children']);
    }
    public function test(){
        return json_encode("{
            'root': [
                {
                    'key': '0',
                    'label': 'Documents',
                    'data': 'Documents Folder',
                    'icon': 'pi pi-fw pi-inbox',
                    'children': [{
                        'key': '0-0',
                        'label': 'Work',
                        'data': 'Work Folder',
                        'icon': 'pi pi-fw pi-cog',
                        'children': [{ 'key': '0-0-0', 'label': 'Expenses.doc', 'icon': 'pi pi-fw pi-file', 'data': 'Expenses Document' }, { 'key': '0-0-1', 'label': 'Resume.doc', 'icon': 'pi pi-fw pi-file', 'data': 'Resume Document' }]
                    },
                    {
                        'key': '0-1',
                        'label': 'Home',
                        'data': 'Home Folder',
                        'icon': 'pi pi-fw pi-home',
                        'children': [{ 'key': '0-1-0', 'label': 'Invoices.txt', 'icon': 'pi pi-fw pi-file', 'data': 'Invoices for this month' }]
                    }]
                },
                {
                    'key': '1',
                    'label': 'Events',
                    'data': 'Events Folder',
                    'icon': 'pi pi-fw pi-calendar',
                    'children': [
                        { 'key': '1-0', 'label': 'Meeting', 'icon': 'pi pi-fw pi-calendar-plus', 'data': 'Meeting' },
                        { 'key': '1-1', 'label': 'Product Launch', 'icon': 'pi pi-fw pi-calendar-plus', 'data': 'Product Launch' },
                        { 'key': '1-2', 'label': 'Report Review', 'icon': 'pi pi-fw pi-calendar-plus', 'data': 'Report Review' }]
                },
                {
                    'key': '2',
                    'label': 'Movies',
                    'data': 'Movies Folder',
                    'icon': 'pi pi-fw pi-star-fill',
                    'children': [{
                        'key': '2-0',
                        'icon': 'pi pi-fw pi-star-fill',
                        'label': 'Al Pacino',
                        'data': 'Pacino Movies',
                        'children': [{ 'key': '2-0-0', 'label': 'Scarface', 'icon': 'pi pi-fw pi-video', 'data': 'Scarface Movie' }, { 'key': '2-0-1', 'label': 'Serpico', 'icon': 'pi pi-fw pi-video', 'data': 'Serpico Movie' }]
                    },
                    {
                        'key': '2-1',
                        'label': 'Robert De Niro',
                        'icon': 'pi pi-fw pi-star-fill',
                        'data': 'De Niro Movies',
                        'children': [{ 'key': '2-1-0', 'label': 'Goodfellas', 'icon': 'pi pi-fw pi-video', 'data': 'Goodfellas Movie' }, { 'key': '2-1-1', 'label': 'Untouchables', 'icon': 'pi pi-fw pi-video', 'data': 'Untouchables Movie' }]
                    }]
                }
            ]
        }");
    }
}
