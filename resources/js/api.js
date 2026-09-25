const request = async (url, options = {}) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
	const response = await fetch(url, {
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'X-Requested-With': 'XMLHttpRequest',
            ...(csrfToken && {
                'X-CSRF-TOKEN': csrfToken
            }),
			...(options.headers || {}),
		},
		credentials: 'same-origin',
		...options,
	});

	const contentType = response.headers.get('content-type') || '';
	const data = contentType.includes('application/json')
		? await response.json()
		: await response.text();

	if (!response.ok) {
		const error = new Error(data?.message || `Request failed: ${response.status}`);
		error.status = response.status;
		error.data = data;
		throw error;
	}

	return data;
};

const api = {
	get: (url, options = {}) => request(url, { ...options, method: 'GET' }),
	post: (url, body, options = {}) => request(url, {
		...options,
		method: 'POST',
		body: JSON.stringify(body),
	}),
	put: (url, body, options = {}) => request(url, {
		...options,
		method: 'PUT',
		body: JSON.stringify(body),
	}),
	patch: (url, body, options = {}) => request(url, {
		...options,
		method: 'PATCH',
		body: JSON.stringify(body),
	}),
	delete: (url, options = {}) => request(url, { ...options, method: 'DELETE' }),
};

export { request };
export default api;
