import NavLink from "@/Components/NavLink.jsx";

export default function WelcomeNav({ auth, cartItemsCount, cartsHistoryCount, salesCount }) {
    let isCustomer = false
    let isSupplier = false
    let isAdmin = false

    for(let x = 0; x < auth.user.roles.length; x++){
        if( auth.user.roles[x].name == 'admin'){
            isAdmin = true;
        }
        if( auth.user.roles[x].name == 'supplier'){
            isSupplier = true;
        }
        if( auth.user.roles[x].name == 'customer'){
            isCustomer = true;
        }
    }
    return (
        <nav className="">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-16">
                    <div className="flex">
                        <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink className="px-3 py-2" href={route('welcome')}>
                                Welcome
                            </NavLink>
                            {auth.user ? (
                                <>
                                    <NavLink
                                        className="px-3 py-2"
                                        method="post" href={route('logout')} as="button">
                                        Log out
                                    </NavLink>
                                    <NavLink
                                        className="px-3 py-2"
                                        href={route('profile.edit')}>
                                        Profile
                                    </NavLink>
                                    {isCustomer &&
                                        <>
                                            {cartItemsCount > 0 &&
                                                <>
                                                    <NavLink
                                                        href={route('cart')}
                                                        className="px-3 py-2"
                                                    >
                                                        <div className="indicator">
                                                    <span
                                                        className="indicator-item badge badge-info">{cartItemsCount}</span>
                                                            <div className="">Cart&nbsp;&nbsp;</div>
                                                        </div>
                                                    </NavLink>
                                                </>
                                            }
                                            {cartsHistoryCount > 0 &&
                                                <NavLink
                                                    href={route('carts.user.history')}
                                                    className="px-3 py-2"
                                                >
                                                    Previous Purchases
                                                </NavLink>
                                            }
                                        </>
                                    }
                                    {isSupplier &&
                                        <>
                                            {salesCount > 0 &&
                                                <NavLink
                                                    href={route('products.sales')}
                                                    className="px-3 py-2"
                                                >
                                                    Sales
                                                </NavLink>
                                            }
                                            <NavLink className="px-3 py-2"
                                                     onClick={(event) => {
                                                         event.preventDefault();
                                                         document.getElementById('mdl_add_product').showModal()
                                                     }}>Add
                                                Product
                                            </NavLink>
                                        </>
                                    }
                                    {isAdmin &&
                                        <NavLink
                                            href={route('dashboard')}
                                            className="px-3 py-2"
                                        >
                                            Dashboard
                                        </NavLink>
                                    }
                                </>
                            ) : (
                                <>
                                    <NavLink
                                        href={route('login')}
                                        className="px-3 py-2"
                                    >
                                        Log in
                                    </NavLink>
                                    <NavLink
                                        href={route('register')}
                                        className="px-3 py-2"
                                    >
                                        Register
                                    </NavLink>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    );
}
