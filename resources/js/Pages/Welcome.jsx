import { Link, Head } from '@inertiajs/react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useState } from 'react';
import NavLink from "@/Components/NavLink.jsx";
import ModalAddProduct from "@/Components/Modals/Product/AddProduct.jsx"
import ModalEditProduct from "@/Components/Modals/Product/EditProduct.jsx"
import WelcomeNav from "@/Components/WelcomeNav.jsx"

export default function Welcome({ auth, laravelVersion, phpVersion, roles, products, cartsHistoryCount, salesCount, cartItemsCount  }) {
    const notify = (text) => toast(text);
    const [state, setState] = useState({
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

    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(product)
        axios.post('/product', product)
            .then(res => {
                window.location.reload()
            })
    }
    if(roles.length == 0) {
        notify("Register and login to start buying")
    }
    return (
        <>
            <ToastContainer/>
            <Head title="Welcome"/>
            <header className="">
                <WelcomeNav
                    auth={auth}
                    roles={roles}
                    cartItemsCount={cartItemsCount}
                    cartsHistoryCount={cartsHistoryCount}
                    salesCount={salesCount}/>
            </header>

            <main>
                <div className="grid grid-cols-5 grid-rows-5 gap-4 px-4 py-4">
                    <div>&nbsp;</div>
                    <div className="col-span-3">
                        <div>
                            <div className="overflow-x-auto">
                                <table className="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        {roles.length > 0 &&
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

                                                {roles.length > 0 &&
                                                    <>
                                                        <td>{data.active}</td>
                                                        <td>
                                                            {roles.includes("customer") &&
                                                                <a className="btn" href="#" onClick={() => {
                                                                    data.quantity = 1;
                                                                    axios.post('/cart/item', data)
                                                                        .then(res => {
                                                                            window.location.reload()
                                                                        })

                                                                }}>Add
                                                                </a>
                                                            }
                                                            {roles.includes("supplier") &&
                                                                <>
                                                                    <button className="btn px-3 py-2" onClick={() => {

                                                                        notify("Product will only be removed if product has no sales!!")
                                                                        axios.delete('/product/' + data.id)
                                                                            .then(res => {
                                                                                window.location.reload()
                                                                            })
                                                                    }}>Remove
                                                                    </button>
                                                                    &nbsp; &nbsp;
                                                                    <button className="btn px-3 py-2" onClick={() => {
                                                                        axios.put('/product/active', {
                                                                            id: data.id,
                                                                            active: 'TOGGLE'
                                                                        })
                                                                            .then(res => {
                                                                                window.location.reload()
                                                                            })
                                                                    }}>Toggle Active
                                                                    </button>
                                                                    &nbsp; &nbsp;
                                                                    <button className="btn px-3 py-2" onClick={() => {
                                                                        setState(data)
                                                                        document.getElementById('mdl_edit_product').showModal()
                                                                    }}>
                                                                        Edit
                                                                    </button>
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
                    </div>
                    <div className="col-start-5">&nbsp;</div>
                    <div className="col-span-5 row-start-2 col-start-3">
                        <footer className="py-16">
                            Demo WorkWize: Laravel v{laravelVersion} (PHP v{phpVersion})
                        </footer>
                    </div>
                </div>
            </main>

            <ModalEditProduct value={state}/>
            <ModalAddProduct/>
        </>
    );
}
