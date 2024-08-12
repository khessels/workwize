import { Head } from '@inertiajs/react';
import 'react-toastify/dist/ReactToastify.css';
import TopNav from "@/Components/TopNav.jsx"
import SideNav from "@/Components/SideNav.jsx"
import NavLink from "@/Components/NavLink.jsx";
import GuestLayout from "@/Layouts/GuestLayout.jsx";

export default function Welcome({ auth, laravelVersion, phpVersion, products, categories, cartsHistoryCount, salesCount, cartItemsCount  }) {
    return (
        <GuestLayout>
            <Head title="Welcome"/>
            
        </GuestLayout>
    );
}
