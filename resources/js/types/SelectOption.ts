export type SelectNumberOption = {
	value: number;
	name: string;
	description?: string;
};

export type SelectStringOption = {
	value: string;
	name: string;
	description?: string;
};

export type SelectOption = SelectNumberOption | SelectStringOption;
