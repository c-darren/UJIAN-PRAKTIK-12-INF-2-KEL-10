<!-- Modal for confirmation -->
<div id="confirmModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="relative w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 id="confirmModalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">
                    Confirm Action
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 
                    hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center 
                    dark:hover:bg-gray-600 dark:hover:text-white" onclick="hideConfirmModal()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" 
                        xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" 
                        d="M4.293 4.293a1 1 0 011.414 0L10 
                        8.586l4.293-4.293a1 1 0 111.414 
                        1.414L11.414 10l4.293 4.293a1 1 0 
                        01-1.414 1.414L10 11.414l-4.293 
                        4.293a1 1 0 01-1.414-1.414L8.586 
                        10 4.293 5.707a1 1 0 010-1.414z" 
                        clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <p id="confirmModalBody" class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    Are you sure you want to proceed?
                </p>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 
                    focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 
                    text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 
                    dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 
                    dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" onclick="hideConfirmModal()">Cancel</button>
                <button id="confirmYesBtn" data-actionUrl="" type="button" class="text-white bg-red-600 
                    hover:bg-red-800 focus:ring-4 focus:outline-none 
                    focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:focus:ring-red-800">Confirm</button>
            </div>
        </div>
    </div>
</div>