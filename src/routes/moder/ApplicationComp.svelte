<script>
	import OneApplication from "./OneApplication.svelte";
	/**@typedef {import("./type.js").application} application*/

	/**@type {{[y: number]: application[]}}*/
	export let obj_element;
	/**@type {{[y in "waiting"|"reserved"|"canceled"|"ended"]: application[]}}*/
	export let obj_element1;
	export let title;
	export let reservedBool;
	/**@type {"byStatus"|"byUser"}*/
	export let sortType;


	const titleEnum = {
		reserved:"Reserved",
		waiting:"Waiting",
		canceled:"Canceled",
		ended:"Ended",
	}
</script>

<div class="rounded-lg my-5 bg-gray-700 bg-opacity-40 p-8 text-gray-100">
	<h1>{title}</h1>

	{#if sortType == "byStatus"}
		{#each Object.entries(obj_element) as item, i}
			<div
				class="rounded-lg my-5 bg-gray-700 bg-opacity-60 p-8 text-gray-100 m-4"
			>
				<h2>{`${item[1][0].brand} ${item[1][0].model}`}</h2>

				<div class="px-8 pt-8 text-gray-100  description mx-4 text-sm">
					<div>Login</div>
					<div>Reservation time</div>
					<div>Start time</div>
					<div>End time</div>
				</div>

				{#each item[1] as item, i}
					<OneApplication application={item} {reservedBool} {sortType} />
				{/each}
			</div>
		{/each}
	{:else}
		{#each Object.entries(obj_element1) as item, i}
			<div
				class="rounded-lg my-5 bg-gray-700 bg-opacity-60 p-8 text-gray-100 m-4"
			>
				<h2>{titleEnum[item[0]]}</h2>

				<div class="px-8 pt-8 text-gray-100  description mx-4 text-sm">
					<div>Name</div>
					<div>Reservation time</div>
					<div>Start time</div>
					<div>End time</div>
				</div>

				{#each item[1] as item, i}
					<OneApplication application={item} reservedBool = {item.status == "reserved"} {sortType} />
				{/each}
			</div>
		{/each}
	{/if}
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
