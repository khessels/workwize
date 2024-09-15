import {useEffect, useState} from "react";
export default function ItemCount( { defaultValue }) {
    const [itemCount, SetItemCount] = useState( defaultValue);
    useEffect(() => {
        axios.get('/cart/items/count' )
            .then(response => {
                let val = response.data === 0 ? defaultValue : response.data;
                SetItemCount( val);
            })
    }, []);
    return ( <>{itemCount}</> );
}
