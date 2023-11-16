export type SelectNumberOption = {
    value: number;
    name: string;
};

export type SelectStringOption = {
    value: string;
    name: string;
};

export type SelectOption = SelectNumberOption | SelectStringOption;
