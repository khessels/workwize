import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import { useState } from 'react';

export default function CartsHistory({ auth, carts }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Cart</h2>}
        >
            <Head title="Cart"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <table
                                className="table hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover dark:block">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {carts.map(function (cart, index) {
                                    return (
                                        <>
                                            <tr key={index} className="rowhighlight">
                                                <td>{cart.id}</td>
                                                <td>{cart.updated_at}</td>
                                                <td>{cart.amount}</td>
                                                <td>
                                                    <button className="btn" onClick={() => {
                                                    }}> Print invoice ?
                                                    </button>
                                                </td>
                                            </tr>
                                            <table
                                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                                <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {cart.items.map(function (item, indexItem) {
                                                    return (
                                                        <tr key={indexItem}>
                                                            <td>{item.product.name}</td>
                                                            <td>{item.quantity}</td>
                                                            <td>{item.price}</td>
                                                        </tr>
                                                    )
                                                })}
                                                </tbody>
                                            </table>
                                        </>
                                    )
                                })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
