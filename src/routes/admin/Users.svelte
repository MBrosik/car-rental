<script>
	import OneUser from "./oneUser.svelte";
	import arrayGroupBy from "../../utils/arrayGroupBy";
	import Default from "../../DEFAULT.svelte";

	/**@typedef {import("./userType").UsersData} UsersData*/

	/**@type {UsersData[]}*/
	let data1;
	/**@type {{[x in keyof {0,1}]:UsersData[]}}*/
	let data;

	async function getUsers() {
		let res = await fetch("./php/admin/getUsers.php");
		data1 = await res.json();		

		data = arrayGroupBy(data1, (el) => el["activated"]);

		return data;
	}

	let awaitUsers = getUsers();

	// -----------
	// filtering
	// -----------

	let textObject = {
		moderator: ["Others", "Moders"],
		admin: ["Others", "Admins"],
		user: ["Others", "Users"],
		activated: ["Inactive accounts", "Active accounts"],
	};

	/**@type {"moderator"|"admin"|"activated"|"user"}*/
	let groupByFilterBind = "activated";

	let filter_user = "";

	function filter_user_fun() {
		let filtered_users = data1.filter((el) =>
			el.login.toLowerCase().includes(filter_user.toLowerCase())
		);

		if (groupByFilterBind == "activated") {
			awaitUsers = Promise.resolve(
				arrayGroupBy(filtered_users, (el) => el[groupByFilterBind])
			);
		} else {
			awaitUsers = Promise.resolve({
				0: filtered_users.filter((el) => el.user_type != groupByFilterBind),
				1: filtered_users.filter((el) => el.user_type == groupByFilterBind),
			});
		}
	}
</script>

<main class="w-max m-auto">
	{#await awaitUsers}
		<h2 class="text-white text-center m-20 text-5xl">Loading...</h2>
	{:then res}		
		<div class="">
			<div
				class="p-5 w-min mx-auto rounded-lg border-solid border-8 border-gray-700"
			>
				<h1 class="text-gray-200 w-full text-center mb-6">Options</h1>
				<select
					bind:value={groupByFilterBind}
					on:change={filter_user_fun}
					class="bg-transparent text-gray-100 w-full"
				>
					<option class="bg-gray-900 text-gray-100" value="moderator"
						>Moderator</option
					>
					>
					<option class="bg-gray-900 text-gray-100" value="admin"
						>Admin</option
					>
					<option class="bg-gray-900 text-gray-100" value="user"
						>User</option
					>
					>
					<option class="bg-gray-900 text-gray-100" value="activated"
						>Active</option
					>
				</select>
				<h1 class="text-gray-200 w-full text-center my-6">User name</h1>
				<input
					class="bg-transparent text-gray-200"
					type="text"
					bind:value={filter_user}
					on:change={filter_user_fun}
				/>
			</div>
			<!-- -------------------------------- -->
			<!-- activated/disactivated container -->
			<!-- -------------------------------- -->
			{#each Object.entries(res).sort((el1, el2) => parseInt(el2[0]) - parseInt(el1[0])) as item, ind}
				<div
					class="rounded-lg my-5 bg-gray-800 bg-opacity-40 p-8 text-gray-100"
				>					
					<h2>{textObject[groupByFilterBind][item[0]]}</h2>

					<!-- ---------------- -->
					<!-- column container -->
					<!-- ---------------- -->
					<div class={`px-8 pt-8 text-gray-100 mx-4 text-sm description`}>
						<div>ID</div>
						<div>Login</div>
						<div>Typ</div>
						<div>Activated</div>
					</div>
					
					<!-- -------------- -->
					<!-- for each user  -->
					<!-- -------------- -->
					{#each item[1] as item, ind}
						<OneUser index={ind} userData={item} />
					{/each}
					
				</div>
			{/each}
		</div>
	{:catch}
		<Default />
	{/await}
</main>

<style>	
	.description {
		display: grid;
		grid-template-columns: repeat(5, 1fr);
		gap: 10px;
	}	
	.description div {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
