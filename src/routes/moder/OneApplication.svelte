<script>
	/**@typedef {import("./type.js").application} application*/

	/**
	 * Props
	 */

	/**@type {application}*/
	export let application;
	/**@type {boolean}*/
	export let reservedBool;
	/**@type {"byStatus"|"byUser"}*/
	export let sortType;

	/**
	 * Funcrion handlers
	 */

	async function submit() {
		let form = new FormData();
		form.append("id", application.id);

		await fetch("./php/moder/accept_application.php", {
			method: "POST",
			body: form,
		});

		window.location.reload();
	}

	function start_time_handler() {
		const { start_time, end_time } = application;
		if (new Date(start_time).getTime() > new Date(end_time).getTime()) {
			application.end_time = application.start_time;
		}
	}
	function end_time_handler() {
		const { start_time, end_time } = application;
		if (new Date(start_time).getTime() > new Date(end_time).getTime()) {
			application.start_time = application.end_time;
		}
	}

	/**
	 * reserved functions
	 */

	async function updateDates() {
		let form = new FormData();
		form.append("id", application.id);
		form.append("start_time", application.start_time);
		form.append("end_time", application.end_time);

		let res = await fetch("./php/moder/reserved/edit_dates.php", {
			method: "POST",
			body: form,
		});

		window.location.reload();
	}

	async function deleteReservation() {
		let form = new FormData();
		form.append("id", application.id);

		let res = await fetch("./php/moder/reserved/delete_reservation.php", {
			method: "POST",
			body: form,
		});

		window.location.reload();
	}
</script>

<div
	class="rounded-lg my-5 bg-gray-600 bg-opacity-40 p-8 text-gray-100 m-4 text-sm description"
>
	<div>
		{sortType == "byStatus"
			? application.login
			: `${application.brand} ${application.model}`}
	</div>
	<div>{application.reserve_time}</div>

	<!-- --------- -->
	<!-- Date edit -->
	<!-- --------- -->
	{#if reservedBool}
		<input
			bind:value={application.start_time}
			on:input={start_time_handler}
			class="text-center h-14 border-1 bg-transparent text-gray-100"
			type="date"
			name=""
		/>
		<input
			bind:value={application.end_time}
			on:input={end_time_handler}
			class="text-center h-14 border-1 bg-transparent text-gray-100"
			type="date"
			name=""
		/>
	{:else}
		<div>{application.start_time}</div>
		<div>{application.end_time}</div>
	{/if}

	<!-- ---------------- -->
	<!-- Button container -->
	<!-- ---------------- -->
	<div class="flex flex-col">
		{#if application.status == "reserved" || application.status == "waiting"}
			<button
				class="text-white bg-green-500 border-0 py-2 px-8 my-2 focus:outline-none hover:bg-green-600 rounded text-sm"
				on:click={reservedBool ? updateDates : submit}
			>
				{reservedBool ? "Zaktualizuj" : "Zatwierdź"}
			</button>
			<button
				class="text-white bg-red-500 border-0 py-2 px-8 my-2 focus:outline-none hover:bg-red-600 rounded text-sm"
				on:click={deleteReservation}
			>
				Usuń
			</button>
		{/if}
	</div>
</div>

<style>
	.description {
		display: grid;
		grid-template-columns: repeat(5, 200px);
		gap: 8px;
		align-items: center;
	}
	.description div {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
