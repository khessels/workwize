export const usePost = () => {
    const [status, setStatus] = useState()

    const handlePost = useCallback(async (url, data) => {
        // your api request logic in here, bellow only show example
        try {
            const {data, status} = await apiCall(url, data)
            if (data && status === 200) navigate(`/`)
        } catch (error) {
            console.log(error)
        }
    }, [])

    return { handlePost }
    // to return status to component, you can use bellow.
    // return { status, handlePost }
}
export default usePost;
