import NavLink from "@/Components/NavLink.jsx";
import {publish, subscribe} from "@/Components/js/Events.js";
export default function SideNav({ auth, cartItemsCount, cartsHistoryCount, salesCount }) {
    return (
        <nav className="">

            {auth.user ? (
                <>

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
                    <br/>
                    <NavLink
                        href={route('register')}
                        className="px-3 py-2"
                    >
                        Register
                    </NavLink>
                    <br/>
                </>
            )}
        </nav>
    );
}
