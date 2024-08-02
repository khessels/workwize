import { Link, Head } from '@inertiajs/react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useState } from 'react';

export default function Welcome({ auth, laravelVersion, phpVersion, roles, products, cartsHistoryCount, salesCount, cartItemsCount  }) {
    const notify_item_added_to_cart = () => toast("Added to cart");
    const notify_product_removed = () => toast("Product removed from active inventory (Existing carts or sales not affected)");
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
    return (
        <>
            <ToastContainer/>
            <Head title="Welcome"/>
            <h1>Welcome</h1>

            <div className="">
                <header className="">
                    <nav className="">
                        {auth.user ? (
                            <>
                                <Link
                                    className="px-3 py-2"
                                    method="post" href={route('logout')} as="button">
                                    Log out
                                </Link>
                                <Link
                                    className="px-3 py-2"
                                    href={route('profile.edit')}>
                                    Profile
                                </Link>
                                {roles.includes("customer") &&
                                    <>
                                        {cartItemsCount > 0 &&
                                            <>
                                                <Link
                                                    href={route('cart')}
                                                    className="px-3 py-2"
                                                >
                                                    <div className="indicator">
                                                        <span
                                                            className="indicator-item badge badge-info">{cartItemsCount}</span>
                                                        <div className="">Cart&nbsp;&nbsp;</div>
                                                    </div>
                                                </Link>
                                            </>
                                        }
                                        {cartsHistoryCount > 0 &&
                                            <Link
                                                href={route('carts.user.history')}
                                                className="px-3 py-2"
                                            >
                                                Previous Purchases
                                            </Link>
                                        }
                                    </>
                                }
                                {roles.includes("supplier") &&
                                    <>
                                        {salesCount > 0 &&
                                            <Link
                                                href={route('products.sales')}
                                                className="px-3 py-2"
                                            >
                                                Sales
                                            </Link>
                                        }
                                        <button className="btn px-3 py-2"
                                                onClick={() => document.getElementById('mdl_add_product').showModal()}>Add
                                            Product
                                        </button>
                                    </>
                                }
                            </>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="px-3 py-2"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="px-3 py-2"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>

                <main>
                    <div>
                        <div className="overflow-x-auto">
                            <table className="table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Active</th>
                                    <th>Action</th>
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
                                        </tr>
                                    )
                                })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>

                <footer className="py-16">
                    Demo WorkWize: Laravel v{laravelVersion} (PHP v{phpVersion})
                </footer>
            </div>

            <dialog id="mdl_edit_product" className="modal">
                <div className="modal-box">
                    <h3 className="font-bold text-lg">Edit Product: {state.name}</h3>

                    <input type="hidden" name='id' value={state.id}/>
                    <label>Name:
                        <input className="input" defaultValue={state.name} onChange={event => {
                            state.name = event.target.value;
                        }}/>
                    </label>
                    <br/>
                    <label>Stock Quantity:
                        <input className="input max-w-xs" type="number" defaultValue={state.stock} onChange={event => {
                            state.stock = event.target.value;
                        }}/>
                    </label>
                    <br/>
                    <label>Price:
                        <input className="input max-w-xs" type="number" defaultValue={state.price} onChange={event => {
                            state.price = event.target.value;
                        }}/>
                    </label>
                    <label>Active:
                        <select className="select max-w-xs" defaultValue={state.active} onChange={event => {
                            state.active = event.target.value;
                        }}>
                            <option value='YES'>Yes</option>
                            <option value='NO'>No</option>
                        </select>
                    </label>
                    <div className="modal-action">
                        <button className="btn btn-warning" type="submit" onClick={() => {
                            axios.put('/product', state)
                                .then(res => {
                                    window.location.reload()
                                })
                        }}>Save
                        </button>
                        <form method="dialog">
                            <button className="btn">Close</button>
                        </form>
                    </div>
                </div>
            </dialog>
            <dialog id="mdl_add_product" className="modal">
                <div className="modal-box">
                    <form onSubmit={handelSubmit}>
                        <h3 className="font-bold text-lg">Add New Product</h3>
                        <label htmlFor="name">Name:</label>
                        <input type="text" className="input" name="name" onChange={event => {
                            product.name = event.target.value;
                        }}/>

                        <br/>
                        <label htmlFor="stock">Stock Quantity:</label>
                        <input name="stock" className="input max-w-xs" type="number" onChange={event => {
                            product.stock = event.target.value;
                        }}/>

                        <br/>
                        <label htmlFor="price">Price:</label>
                        <input name="price" className="input max-w-xs" type="number" onChange={event => {
                            product.price = event.target.value;
                        }}/>


                        <label htmlFor="active">Active:</label>
                        <select name="active" className="select max-w-xs" onChange={event => {
                            product.active = event.target.value;
                        }}>
                            <option value='YES'>Yes</option>
                            <option value='NO'>No</option>
                        </select>

                        <div className="modal-action">
                            <button className="btn btn-warning" type="submit">Save</button>
                            <button className="btn">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </>
    )
        ;
}
