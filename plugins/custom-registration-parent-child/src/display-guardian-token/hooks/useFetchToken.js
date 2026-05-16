import { useState, useEffect } from '@wordpress/element';

const useFetchToken = (nonce) => {
    const [token, setToken] = useState(null);
    const [status, setStatus] = useState('loading'); // 'loading' | 'success' | 'error'

    useEffect(() => {
        if (!nonce) {
            setStatus('error');
            return;
        }

        const fetchToken = async () => {
            try {
                const response = await fetch('/wp-json/custom-registration/my-token', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': nonce
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    setToken(data.token);
                    setStatus('success');
                } else {
                    setStatus('error');
                }
            } catch (err) {
                setStatus('error');
            }
        };

        fetchToken();
    }, [nonce]);

    return { token, status };
};

export default useFetchToken;