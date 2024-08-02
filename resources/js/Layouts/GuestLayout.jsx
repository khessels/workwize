import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';
import NavLink from "@/Components/NavLink.jsx";

export default function Guest({ children }) {
    return (
        <div className="min-h-screen">
            <nav className="">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="shrink-0 flex items-center">
                                {/*<h2>Sales:</h2>*/}
                            </div>
                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink href={route('welcome')}>
                                    Welcome
                                </NavLink>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>
            <main>{children}</main>
        </div>
    );
}
