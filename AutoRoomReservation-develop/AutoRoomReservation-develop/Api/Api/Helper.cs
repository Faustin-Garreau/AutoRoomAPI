namespace Api
{
    public static class Helper
    {

        public static bool IsObjectNull(object obj)
        {
            try
            {
                if (obj is null)
                {
                    return true;
                }

                var type = obj.GetType();
                var props = type.GetProperties();
                if (props.Length == 0 || obj is string || obj is int)
                {
                    return false;
                }

                var IsNull = props.All((prop) => prop.GetValue(obj, null) == null);

                return IsNull;
            }
            catch (Exception e)
            {
                return true;
            }
        }

    }
}
