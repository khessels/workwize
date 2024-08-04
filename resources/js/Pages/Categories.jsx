import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import { useState } from 'react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';


export default function Categories({ auth, categories }) {
    const notify = (text) => toast(text);
    debugger;
    return (

        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>}
        >
            <ToastContainer/>
            <Head title="Cart"/>
            <div className="grid grid-cols-5 grid-rows-5 gap-4">
                <div>&nbsp;</div>
                <div className="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">

                    {/*<table*/}
                    {/*    className="table flex-1 rounded-[10px] object-top object-cover dark:block">*/}
                    {/*    <thead>*/}
                    {/*    <tr>*/}
                    {/*        <th>Category Id</th>*/}
                    {/*        <th>Parent Id</th>*/}
                    {/*        <th>English</th>*/}
                    {/*        <th>Spanish</th>*/}
                    {/*        <th>Tag</th>*/}
                    {/*        <th>Active</th>*/}
                    {/*        <th>Action</th>*/}
                    {/*    </tr>*/}
                    {/*    </thead>*/}
                    {/*    <tbody>*/}

                    {/*    {categories.map(function (category, index) {*/}
                    {/*        return (*/}
                    {/*            <tr key={index}>*/}

                    {/*                <td>{category.parent.id}</td>*/}
                    {/*                <td>{category.parent.parent_id}</td>*/}
                    {/*                <td>{category.parent.english}</td>*/}
                    {/*                <td>{category.parent.spanish}</td>*/}
                    {/*                <td>{category.parent.tag}</td>*/}
                    {/*                <td>{category.parent.active}</td>*/}
                    {/*                <td>*/}
                    {/*                    <button className="btn" onClick={() => {*/}
                    {/*                        //axios.delete('/cart/item/' + item.id);*/}
                    {/*                        notify("Executed action")*/}
                    {/*                        Inertia.reload();*/}
                    {/*                    }}>Remove*/}
                    {/*                    </button>*/}
                    {/*                </td>*/}
                    {/*            </tr>*/}
                    {/*        )*/}
                    {/*    })}*/}
                    {/*    </tbody>*/}
                    {/*</table>*/}

                </div>
                <div className="col-start-5">
                    &nbsp;
                </div>
                <div className="row-start-2">&nbsp;</div>
                <div className="col-span-3 row-start-2"></div>
                {/*<div className="col-span-3 row-start-2 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">*/}
                {/*    <button className="btn" onClick={() => {*/}
                {/*        axios.post('/cart/checkout');*/}
                {/*        window.location = '/'*/}
                {/*    }}>Check out*/}
                {/*    </button>*/}
                {/*</div>*/}
                <div className="col-start-5 row-start-2">&nbsp;</div>
                <div className="col-span-5 row-start-3">&nbsp;</div>
            </div>
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">


                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
