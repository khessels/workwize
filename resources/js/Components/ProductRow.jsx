import { Fieldset } from 'primereact/fieldset';
import Product from "@/Components/Product"
import { Panel } from 'primereact/panel';
export default function ProductRow({ taggedProductRow} ) {
    return (
        <>
            <Fieldset legend={ taggedProductRow.tag}>
                {
                    taggedProductRow.productTags.map( ( productsByTag, x) =>
                        <Product key={x} product={ productsByTag.product} />
                    )
                }
            </Fieldset>
        </>
    );
}
