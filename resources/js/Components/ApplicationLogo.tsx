import { SVGAttributes } from "react";

export default function ApplicationLogo(props: SVGAttributes<SVGElement>) {
	return (
		<svg
			{...props}
			id="Layer_1"
			data-name="Layer 1"
			xmlns="http://www.w3.org/2000/svg"
			viewBox="0 0 500 500"
		>
			<defs>
				<linearGradient
					id="linear-gradient"
					x1="73.22"
					y1="73.22"
					x2="426.78"
					y2="426.78"
					gradientUnits="userSpaceOnUse"
				>
					<stop offset="0" stopColor="#2563eb" />
					<stop offset="1" stopColor="#0d9488" />
				</linearGradient>
			</defs>
			<circle fill="url(#linear-gradient)" cx="250" cy="250" r="250" />
			<g transform="translate(-45, 20)">
				<path
					d="M290 400L190 200L290 50L290 400Z"
					opacity="1"
					fill="white"
					fillOpacity="1"
				/>
				<path
					d="M400 199.73L300 199.73L300 49.73L400 199.73Z"
					opacity="1"
					fill="white"
					fillOpacity="1"
					transform="translate(5, 0)"
				/>
			</g>
		</svg>
	);
}
