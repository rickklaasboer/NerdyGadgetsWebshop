import {useEffect, useState} from "react";

/**
 * Use cart hook
 */
export default function useCart() {
    const [cart, setCart] = useState(null);
    const [loading, setLoading] = useState(true);

    /**
     * Fetch cart on mount
     */
    useEffect(() => {
        fetchCart();
    }, [])

    /**
     * Fetch cart
     */
    async function fetchCart() {
        try {
            const res = await fetch('/api/cart');
            const json = await res.json();

            if (json) {
                setCart(json)
            }
        } catch (err) {
            setCart([]);
        } finally {
            setLoading(false)
        }
    }

    return {
        cart,
        loading
    }
}