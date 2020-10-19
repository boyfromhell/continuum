window.flashing = () => {
    return {
        paint(color) {
            switch (color) {
                case 'green':
                    document.getElementById('flash').className = 'fixed right-5 bottom-5 cursor-pointer px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-400 active:bg-green-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green';
                    break;
                case 'red':
                    document.getElementById('flash').className = 'fixed right-5 bottom-5 cursor-pointer px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-400 active:bg-red-600 focus:outline-none focus:border-red-700 focus:shadow-outline-red';
                    break;
                default:
                    document.getElementById('flash').className = 'fixed right-5 bottom-5 cursor-pointer px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-400 active:bg-green-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green';
            }
        }
    }
}