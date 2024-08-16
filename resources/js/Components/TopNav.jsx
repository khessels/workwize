import NavLink from "@/Components/NavLink.jsx";
import {publish, subscribe} from "@/Components/js/Events.js";
import { Badge } from 'primereact/badge';
export default function TopNav({ auth, cartItemsCount, cartsHistoryCount, salesCount }) {
    return (
        <nav className="">
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

                    {auth.isCustomer &&
                        <>
                            {cartItemsCount > 0 &&
                                <>
                                    <NavLink
                                        href={route('cart')}
                                        className="px-3 py-2"
                                    >
                                        Cart <Badge severity={"danger"} ></Badge>

                                    </NavLink>
                                </>
                            }
                            {cartsHistoryCount > 0 &&
                                <>
                                    <NavLink
                                        href={route('carts.user.history')}
                                        className="px-3 py-2"
                                    >
                                        Previous Purchases
                                    </NavLink>
                                </>
                            }
                        </>
                    }
                    {(auth.isAdmin || auth.isSupplier) &&
                        <>
                            {salesCount > 0 &&
                                <>
                                    <NavLink
                                        href={route('products.sales')}
                                        className="px-3 py-2"
                                    >
                                        Sales
                                    </NavLink>
                                    <br/>
                                </>
                            }
                            <NavLink
                                href={route('dashboard')}
                                className="px-3 py-2"
                            >
                                Dashboard
                            </NavLink>
                            <br/>
                        </>
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
        </nav>
    );
}
