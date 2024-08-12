import { Head } from '@inertiajs/react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useState, useEffect } from 'react';
import SideNav from "@/Components/SideNav.jsx"
import ModalAddProduct from "@/Components/Modals/Product/AddProduct.jsx"
import ModalEditProduct from "@/Components/Modals/Product/EditProduct.jsx"
import ModalQuantity from "@/Components/Modals/Quantity.jsx"
import {publish, subscribe} from "@/Components/js/Events.js";
import { Button } from "primereact/button";
import { Inertia} from "@inertiajs/inertia";
import { Toast} from "primereact/toast";
import { TreeTable} from "primereact/treetable";
import { Column} from "primereact/column";
import AuthenticatedBackendLayout from "@/Layouts/AuthenticatedBackendLayout.jsx";
import { NodeProducts } from '@/Services/NodeProducts.jsx';

export default function Products({ auth, laravelVersion, phpVersion, products, categories, cartsHistoryCount, salesCount, cartItemsCount  }) {
    const notify = (text) => toast(text);
    const [state, setState] = useState({
        quantity: undefined,
        id      : undefined,
        stock   : undefined,
        name    : undefined,
        active  : undefined,
        price   : undefined
    });
    const [product, setProduct] = useState({
        stock   : 0,
        name    : '',
        active  : 'YES',
        price   : 0
    });
    const dlgHeader = "Product Quantity";
    let cbQuantityEventName = "event-product-quantity";
    subscribe(cbQuantityEventName, (data) =>{
        state.quantity = data.detail;
        axios.post('/cart/item', state)
            .then(res => {
                Inertia.reload()
            })
    });
    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(product)
        axios.post('/product', product)
            .then(res => {
                Inertia.reload()
            })
    }
    if(auth.isPublic) {
        notify("Register and login to start buying")
    }

    return (
        <AuthenticatedBackendLayout
            auth={auth}
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>}
        >

            <Head title="Products"/>
            <Toast ref={toast}/>

            <div className="w-full flex flex-col sm:flex-row flex-wrap sm:flex-nowrap py-4 flex-grow">
                <div className="w-fixed flex-shrink flex-grow-0 px-4">
                    <div className="sticky top-0 p-4 w-full h-full">
                        <Button onClick={(event) => {
                            event.preventDefault();
                            publish('modal-product-add', "show")
                        }}>Add Product</Button>
                    </div>
                </div>
                <main role="main" className="w-full flex-grow pt-1 px-3">
                    <div className="flex">
                        <div className="overflow-x-auto">
                            <table className="table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    {!auth.isPublic &&
                                        <>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </>
                                    }
                                </tr>
                                </thead>
                                <tbody>
                                {products.map(function (data, index) {
                                    let url = '/product/' + data.id
                                    return (
                                        <tr key={index}>
                                            <td>{data.id}</td>
                                            <td>{data.name}</td>
                                            <td>{data.stock}</td>
                                            <td>{data.price}</td>

                                            {!auth.isPublic &&
                                                <>
                                                    <td>{data.active}</td>
                                                    <td>
                                                        {auth.isCustomer &&
                                                            <>
                                                                <Button onClick={() => {
                                                                    data.quantity = 1;
                                                                    setState(data)
                                                                    publish('modal-quantity', "show")
                                                                }}>Add
                                                                </Button>
                                                                &nbsp;
                                                            </>
                                                        }
                                                        {(auth.isSupplier || auth.isAdmin) &&
                                                            <>
                                                                <Button onClick={() => {

                                                                    axios.delete('/product/' + data.id)
                                                                        .then(res => {
                                                                            if (res.data === 'DELETED') {
                                                                                Inertia.reload()
                                                                            } else {
                                                                                notify("Product will only be removed if product has no sales!!")
                                                                            }
                                                                        })
                                                                }}>Remove
                                                                </Button>
                                                                &nbsp;
                                                                <Button onClick={() => {
                                                                    axios.put('/product/active', {
                                                                        id: data.id,
                                                                        active: 'TOGGLE'
                                                                    })
                                                                        .then(res => {
                                                                            Inertia.reload()
                                                                        })
                                                                }}>Toggle Active
                                                                </Button>
                                                                &nbsp;
                                                                <Button onClick={() => {
                                                                    setState(data)
                                                                    publish('modal-product-edit', "show")
                                                                }}>
                                                                    Edit
                                                                </Button>
                                                            </>
                                                        }

                                                    </td>
                                                </>
                                            }
                                        </tr>
                                    )
                                })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
                <div className="flex-grow-0 px-2">
                    <div className="flex sm:flex-col px-2">
                        Sidebar
                    </div>
                </div>
            </div>
            <ModalEditProduct value={state}/>
            <ModalAddProduct categories={categories}/>
            <ModalQuantity
                dlgHeader={dlgHeader}
                initialValue={1}
                minValue={1}
                maxValue={10}
                closeOnSubmit={true}
                cbEventName={cbQuantityEventName}
            />
        </AuthenticatedBackendLayout>

    );
}
