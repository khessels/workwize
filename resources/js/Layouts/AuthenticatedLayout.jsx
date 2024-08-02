import { useState } from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link } from '@inertiajs/react';

export default function Authenticated({ user, header, children }) {

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

                        {/*<div className="hidden sm:flex sm:items-center sm:ms-6">*/}
                        {/*    <div className="ms-3 relative">*/}
                        {/*        <Dropdown>*/}
                        {/*            <Dropdown.Trigger>*/}
                        {/*                <span className="inline-flex rounded-md">*/}
                        {/*                    <button*/}
                        {/*                        type="button"*/}
                        {/*                        className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"*/}
                        {/*                    >*/}
                        {/*                        {user.name}*/}

                        {/*                        <svg*/}
                        {/*                            className="ms-2 -me-0.5 h-4 w-4"*/}
                        {/*                            xmlns="http://www.w3.org/2000/svg"*/}
                        {/*                            viewBox="0 0 20 20"*/}
                        {/*                            fill="currentColor"*/}
                        {/*                        >*/}
                        {/*                            <path*/}
                        {/*                                fillRule="evenodd"*/}
                        {/*                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"*/}
                        {/*                                clipRule="evenodd"*/}
                        {/*                            />*/}
                        {/*                        </svg>*/}
                        {/*                    </button>*/}
                        {/*                </span>*/}
                        {/*            </Dropdown.Trigger>*/}

                        {/*            <Dropdown.Content>*/}
                        {/*                <Dropdown.Link href={route('profile.edit')}>Profile</Dropdown.Link>*/}
                        {/*                <Dropdown.Link href={route('logout')} method="post" as="button">*/}
                        {/*                    Log Out*/}
                        {/*                </Dropdown.Link>*/}
                        {/*            </Dropdown.Content>*/}
                        {/*        </Dropdown>*/}
                        {/*    </div>*/}
                        {/*</div>*/}
                    </div>
                </div>
            </nav>
            <main>{children}</main>
        </div>
    );
}
