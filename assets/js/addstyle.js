let style1 = ["mt-1","py-2", "px-3" ,"border" ,"border-gray-300", "bg-white", "rounded-md", "shadow-sm", "focus:outline-none" ,"focus:shadow-outline-purple", "dark:text-gray-300" ,"dark:focus:shadow-outline-gray","sm:text-sm"];

let style2 = ["pl-8" ,"pr-2", "sm:text-sm" ,"text-gray-700" ,"placeholder-gray-600", "bg-white" ,"border","border-gray-300", "rounded-md", "dark:placeholder-gray-500" ,"dark:focus:shadow-outline-gray", "dark:focus:placeholder-gray-600" ,"dark:bg-gray-700", "dark:text-gray-200" ,"focus:placeholder-gray-500" ,"focus:bg-white" ,"focus:border-purple-300", "focus:outline-none" ,"focus:shadow-outline-purple" ,"form-input"];

let style3 = ["flex","justify-between"];

let style4 = ["text-purple-600" ,"form-checkbox" ,"focus:border-purple-400" ,"focus:outline-none", "focus:shadow-outline-purple" ,"dark:focus:shadow-outline-gray"];

let select = document.querySelector('.dataTable-selector');

select.classList.remove('.dataTable-selector');
select.classList.add(...style1);

let search = document.querySelector('.dataTable-input');

search.classList.remove('.dataTable-input');
search.classList.add(...style2);


var checkboxes = document.getElementById("columns");

checkboxes.classList.add(...style3);
checkboxes.style.width = "30%";