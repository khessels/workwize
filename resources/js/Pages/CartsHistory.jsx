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
            <Head title="Cart" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <table
                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Id</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {Object.entries(cartItems).map(([index, data]) => (
                                    <tr key={index}>
                                        <td>{index}</td>
                                        <td>{data.id}</td>
                                        <td>{data.name}</td>
                                        <td>{data.quantiy}</td>
                                        <td>{data.price}</td>
                                        <td>
                                            <button onClick={() => {
                                                debugger;
                                                // todo: remove from array
                                                setCartItems([
                                                    ...cartItems,
                                                    data
                                                ]);
                                            }}>Remove item from cart
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {/*{products.map(function (data, index) {*/}
                                {/*    return (*/}
                                {/*        <tr key={index}>*/}
                                {/*            <td>{index}</td>*/}
                                {/*            <td>{data.id}</td>*/}
                                {/*            <td>{data.name}</td>*/}
                                {/*            <td>{data.stock}</td>*/}
                                {/*            <td>{data.price}</td>*/}
                                {/*            <td>*/}
                                {/*                <button onClick={() => {*/}
                                {/*                    debugger;*/}
                                {/*                    setCartItems([*/}
                                {/*                        ...cartItems,*/}
                                {/*                        data*/}
                                {/*                    ]);*/}
                                {/*                }}>Add*/}
                                {/*                </button>*/}
                                {/*            </td>*/}
                                {/*        </tr>*/}
                                {/*    )*/}
                                {/*})}*/}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
