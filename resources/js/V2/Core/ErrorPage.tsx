import {isRouteErrorResponse, useRouteError} from 'react-router-dom';

export default function ErrorPage() {
    const error = useRouteError();

    if (isRouteErrorResponse(error)) {
        return <div>{error.status} {error.statusText}.</div>;
    }

    return <div>Unknown error occurred.</div>;
}
