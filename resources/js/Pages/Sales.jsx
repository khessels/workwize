import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Sales({ auth, sales }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Sales</h2>}
        >
            <Head title="Sales" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <table
                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                {sales.map(function (user, indexUser) {
                                    return (
                                        <>
                                            <tr key={indexUser}>
                                                <td>{user.id}</td>
                                                <td>{user.email}</td>
                                                <td>{user.name}</td>
                                                <td>{user.created_at}</td>
                                                {/*<td>*/}
                                                {/*    <button onClick={() => {*/}
                                                {/*    }}> Print invoice ?*/}
                                                {/*    </button>*/}
                                                {/*</td>*/}
                                            </tr>
                                            <table
                                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                                <thead>
                                                <tr>
                                                    <th>Cart id</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {user.carts.map(function (cart, indexCart) {

                                                    return (
                                                        <>
                                                            <tr key={indexCart}>
                                                                <td>{cart.id}</td>
                                                                <td>{cart.total}</td>
                                                                <td>{cart.updated_at}</td>
                                                            </tr>
                                                            <table
                                                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block">
                                                                <thead>
                                                                <tr>
                                                                    <th>Product id</th>
                                                                    <th>Name</th>
                                                                    <th>Price</th>
                                                                    <th>Quantity</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                {cart.items.map(function (item, indexItem) {
                                                                    return(
                                                                        <tr key={indexItem}>
                                                                            <td>{item.product.id}</td>
                                                                            <td>{item.product.name}</td>
                                                                            <td>{item.price}</td>
                                                                            <td>{item.quantity}</td>
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
