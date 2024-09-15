import {useEffect, useState} from "react";
export default function History( { defaultValue }) {
    const [itemCount, SetItemCount] = useState( defaultValue);
    useEffect(() => {
        axios.get('/cart/history' )
            .then(response => {
                let val = response.data === 0 ? defaultValue : response.data;
                SetItemCount( val);
            })
    }, []);
    return ( <>{itemCount}</> );
}
