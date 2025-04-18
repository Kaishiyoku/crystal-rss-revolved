import { WritableAtom } from "jotai/index";
import { ReactNode } from "react";
import { useHydrateAtoms } from "jotai/utils";

export default function AtomsHydrator({
	atomValues,
	children,
}: {
	atomValues: Iterable<
		readonly [WritableAtom<unknown, [unknown], unknown>, unknown]
	>;
	children: ReactNode;
}) {
	useHydrateAtoms(new Map(atomValues));

	return children;
}
