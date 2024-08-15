import { Toast } from 'primereact/toast';
import {useRef} from "react";

const toast = useRef(null);
function showToast (detail, title = 'Categories', severity= 'info')
{
    Toast.current.show({ severity: severity, summary: title, detail: detail });
}

export { showToast};
