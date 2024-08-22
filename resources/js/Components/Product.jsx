import { Card } from 'primereact/card';

export default function Product( { product} ) {
    return (
        <>
            <div className={"w-80"}>
                <Card title={product.name} className="">
                    <p className="m-0">
                        { product.name}
                    </p>
                </Card>
            </div>
        </>
    );
}
