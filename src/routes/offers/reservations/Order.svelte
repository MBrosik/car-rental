<script>
	import Default from "../../../DEFAULT.svelte";

	/**
	 * @typedef {Object} carDef
	 * @property {String} id
	 * @property {String} brand
	 * @property {String} model
	 * @property {String} img
	 * @property {String} car_id
	 * @property {Number} price_per_day
	 * @property {string} reserve_time
	 * @property {string} start_time
	 * @property {string} end_time
	 * @property {string} qr_code
	 * @property {number} date_now
	 * @property {"waiting"|"reserved"|"canceled"|"ended"} status
	 */

	/**@type {{orderId:string, orderType:"reserved"|"reserved_archive"}}*/
	export let params = {};

	/**@type {carDef}*/
	let data;

	async function getOrder() {
		let form = new FormData();
		form.set("id", params.orderId);
		form.set("sql_type", params.orderType);

		let res = await fetch("./php/offers/reserved/get_one_reserved.php", {
			method: "POST",
			body: form,
		});

		data = await res.json();

		return data;
	}

	let awaitGetOrder = getOrder();

	/**
	 * calculate QR time
	 */

	function getQRTime(start_time, date_now) {
		let parsed_start = new Date(start_time).getTime();
		let calc = (parsed_start - date_now) / (1000 * 60 * 60 * 24);

		return Math.floor(calc);
	}

	/**
	 * calculate payment and show it in html
	 */
	function getPayment(start_time, end_time, price_per_day) {
		let parsed_start = new Date(start_time).getTime();
		let parsed_end = new Date(end_time).getTime();
		return (
			((parsed_end - parsed_start) / (24 * 60 * 60 * 1000)) *
			price_per_day
		).toFixed(2);
	}

	/**
	 * On click handler
	 */
	async function reject_handler() {
		let conf = confirm("Czy na pewno chcesz anulować?");

		if (conf) {
			let form = new FormData();
			form.append("id", data.car_id);

			let res = await fetch("./php/offers/reject_offer.php", {
				method: "POST",
				body: form,
			});

			window.location.href = "./#/your_orders/";
		}
	}
</script>

<main class="w-full">
	<!-- {params.orderId} -->

	{#await awaitGetOrder}
		<h2 class="text-white text-center m-20 text-5xl">Ładuję</h2>
	{:then res}
		<div
			class="rounded-lg my-5 mx-auto bg-gray-800 bg-opacity-40 p-8 text-gray-100 m-4 w-96 flex flex-col items-center"
		>
			<img
				src={res.img}
				alt=""
				class="w-90 rounded-lg border-solid border-8 border-gray-700 mb-6"
			/>
			<!-- ------- -->
			<!-- QR CODE -->
			<!-- ------- -->
			{#if res.qr_code}
				<img
					src={res.qr_code}
					alt=""
					class="w-90 rounded-lg border-solid border-8 border-gray-700"
				/>
				{#if new Date(res.end_time).getTime() > res.date_now && params.orderType == "reserved"}
					<h2 class="w-2/3 text-yellow-600 text-xs text-center">
						Koniec za {getQRTime(res.end_time, res.date_now)} dni
					</h2>
				{/if}
			{:else if params.orderType == "reserved"}
				<h2 class="w-2/3 text-green-600 text-xs text-center">
					Kod QR otrzymasz za {getQRTime(res.start_time, res.date_now)} dni
				</h2>
			{/if}

			<!-- -------------------- -->
			<!-- detention of the car -->
			<!-- -------------------- -->

			{#if new Date(res.end_time).getTime() < res.date_now && params.orderType == "reserved"}
				<h2 class="w-2/3 text-red-600 text-xs text-center">Car is held</h2>
			{/if}
			<h2
				class="tracking-widest text-base text-center w-2/3 h-14 title-font font-medium text-gray-500 mb-1 my-2"
			>
				{res.brand}
				{res.model}
			</h2>

			<!-- ----- -->
			<!-- dates -->
			<!-- ----- -->
			<span class="w-2/3 text-left text-gray-500 text-xs my-2">
				Reservation Date:
			</span>
			<input
				value={res.reserve_time}
				class="w-2/3 border-1 bg-transparent text-gray-500"
				type="text"
				disabled
			/>
			<span class="w-2/3 text-left text-gray-500 text-xs my-2">
				Start Date:
			</span>
			<input
				value={res.start_time}
				class="w-2/3 border-1 bg-transparent text-gray-500"
				type="date"
				disabled
			/>

			<span class="w-2/3 text-left text-gray-500 text-xs my-2">
				End Date:
			</span>
			<input
				value={res.end_time}
				class="w-2/3 border-1 bg-transparent text-gray-500"
				type="date"
				disabled
			/>

			<!-- -------- -->
			<!-- payments -->
			<!-- -------- -->
			<h2 class="text-gray-500 text-left text-xs my-2 w-2/3">
				Price per hour: {res.price_per_day.toFixed(2)} PLN/day
			</h2>

			<h2
				class={`w-2/3 text-left ${
					res.status == "canceled" ? "text-gray-100" : "text-red-600"
				} text-lg my-2`}
			>
				Receivable: {getPayment(
					res.start_time,
					res.end_time,
					res.price_per_day
				)}
				zł
			</h2>

			{#if params.orderType == "reserved"}
				<button
					class="text-white bg-red-500 border-0 py-2 px-8 my-2 focus:outline-none hover:bg-red-600 rounded text-lg"
					on:click={reject_handler}>Cancell</button
				>
			{/if}
		</div>
	{:catch}
		<Default />
	{/await}
</main>
