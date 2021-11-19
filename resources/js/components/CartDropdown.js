import React, {Fragment} from 'react';
import ReactDOM from 'react-dom';
import Spinner from "./Spinner";
import EmptyCart from "./EmptyCart";
import useCart from "../hooks/useCart";

/**
 * Cart dropdown
 */
export default function CartDropdown() {
    const {cart, loading} = useCart();

    if (loading) return <Spinner/>
    if (!cart?.items) return <EmptyCart/>

    return (
        <Fragment>
            <div className="p-3 cart-item-wrapper">
                {cart?.items?.map((product, i) => (
                    <div className="cart-dropdown-row">
                        <div className="item">
                            <div className="image">
                                <a href={'/product/' + product.meta.StockItemID}>
                                    <img
                                        className="w-100 d-block"
                                        src={product.meta.ImagePath ? '/assets/img/products/' + product.meta.ImagePath : '/assets/img/products/no-image.jpg'}
                                        alt={product.meta.StockItemName}
                                    />
                                </a>
                            </div>
                            <div className="meta">
                                <a className="h6" href={'/product/' + product.meta.StockItemID}>
                                    {product.meta.StockItemName.substr(0, 27).trim()}{product.meta.StockItemName.length > 30 && '...'}
                                </a>
                                <small className="text-muted"> ({product.cart.amount})</small>
                            </div>
                            <div className="price font-weight-bold">
                                € {((parseFloat(product.meta.RecommendedRetailPrice) * 1.21) * product.cart.amount).toFixed(2)}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
            <div className="p3">
                <div className="d-flex flex-row justify-content-between align-items-center cart-dropdown-footer border-top">
                    <h5 className="font-weight-bold mb-0">€ {cart.total_price}</h5>
                    <a href="/cart" className="btn btn-primary">
                        Winkelwagen
                        <svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-chevron-right ml-2"
                             fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd"
                                  d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </Fragment>
    )
}

// Render our React component to the DOM
if (document.getElementById('cart_dropdown')) {
    ReactDOM.render(<CartDropdown/>, document.getElementById('cart_dropdown'));
}