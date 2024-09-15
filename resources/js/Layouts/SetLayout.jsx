import GuestLayout from "@/Layouts/GuestLayout.jsx";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import AuthenticatedBackendLayout from "@/Layouts/AuthenticatedBackendLayout.jsx";

export default function SetLayout(layout) {
    const Map = {
        "GuestLayout": GuestLayout,
        "AuthenticatedLayout": AuthenticatedLayout,
        "AuthenticatedBackendLayout": AuthenticatedBackendLayout,
    }
    return Map[layout];
}
