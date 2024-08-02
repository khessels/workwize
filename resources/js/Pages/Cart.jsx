import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import { useState } from 'react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';



export default function Cart({ auth, cart }) {
    const notify_removed = () => toast("Removed cart");
    const notify_checking_out = () => toast("Checked out");

    return (

        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Cart</h2>}
        >
            <ToastContainer />
            <Head title="Cart" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <button onClick={() => {
                                axios.post('/cart/checkout');
                                window.location = '/'
                            }}>Check out
                            </button>
                            <table
                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                <thead>
                                <tr>

                                    <th>Item Id</th>
                                    <th>Product Id</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                {cart.items.map(function (item, index) {
                                    return (
                                        <tr key={index}>

                                            <td>{item.id}</td>
                                            <td>{item.product.id}</td>
                                            <td>{item.product.name}</td>
                                            <td>{item.quantity}</td>
                                            <td>{item.product.price}</td>
                                            <td>
                                                <button onClick={() => {
                                                    axios.delete('/cart/item/' + item.id);
                                                    notify_removed()
                                                    Inertia.reload();
                                                }}>Remove
                                                </button>
                                            </td>
                                        </tr>
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
