import {isRouteErrorResponse, useAsyncError, useRouteError} from 'react-router-dom';

export default function ErrorPage() {
    const error = useRouteError();
    const asyncError = useAsyncError();

    if (asyncError) {
        return <div>Async error occurred: {JSON.stringify({asyncError})}</div>;
    }

    if (isRouteErrorResponse(error)) {
        return <div>{error.status} {error.statusText}.</div>;
    }

    return <div>Unknown error occurred: {JSON.stringify(error)}</div>;
}
