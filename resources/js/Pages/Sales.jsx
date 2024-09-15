import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import SetLayout from "@/Layouts/SetLayout"

export default function Sales({ auth, sales }) {
    let Layout = SetLayout(auth.layout);
    return (

        <Layout
            user={auth.user}
            header={<h2 className="font-semibold text-xl leading-tight">Sales</h2>}
        >
            <Head title="Sales" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden sm:rounded-lg">
                        <div className="p-6 ">
                            <table
                                className="table hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover dark:block">
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
                                            </tr>
                                            <table
                                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover dark:block">
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
                                                                className="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover dark:block">
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
        </Layout>
    );
}
