import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, useRemember} from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia'
import { useState } from 'react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';



export default function Cart({ auth, cart }) {
    const notify_removed = () => toast("Removed cart");
    const notify_checking_out = () => toast("Checked out");
    let cartValue = 0;

    for(let x = 0; x < cart.items.length; x++){
        cartValue += cart.items[x].product.price * cart.items[x].quantity;
    }
    return (

        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Cart</h2>}
        >
            <ToastContainer/>
            <Head title="Cart"/>
            <div className="grid grid-cols-5 grid-rows-5 gap-4">
                <div>&nbsp;</div>
                <div className="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">
                    <table
                        className="table flex-1 rounded-[10px] object-top object-cover dark:block">
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
                                        <button className="btn" onClick={() => {
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
                    <span>Total: {cartValue}</span>
                </div>
                <div className="col-start-5">
                    &nbsp;
                </div>
                <div className="row-start-2">&nbsp;</div>
                <div className="col-span-3 row-start-2 bg-white overflow-hidden shadow-sm sm:rounded-lg py-6">
                    <button className="btn" onClick={() => {
                        axios.post('/cart/checkout');
                        Inertia.reload();
                    }}>Check out
                    </button>
                </div>
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
