type CursorPagination<T> = {
	data: T[];
	next_cursor: string | null;
	next_page_url: string | null;
	path: string;
	per_page: number;
	prev_cursor: string | null;
	prev_page_url: string | null;
};

export default CursorPagination;
