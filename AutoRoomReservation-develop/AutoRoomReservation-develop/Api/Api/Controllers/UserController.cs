using Microsoft.AspNetCore.Mvc;
using Dapper;
using MySql.Data.MySqlClient;
using Models;
using System.Text.Json;
using System.Data;
using Microsoft.Extensions.Primitives;

namespace Api.Controllers
{
    [Route("[controller]/[action]")]
    [ApiController]
    public class UserController : Controller
    {
        private readonly IConfiguration _config;

        private readonly string CS;

        private MySqlConnection Connection { get; set; }

        public UserController(IConfiguration config)
        {
            _config = config;
            CS = _config.GetConnectionString("DB");
            Connection = new(CS);
        }
        
        [HttpGet]
        public string Get([FromQuery] string Id)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                if (string.IsNullOrWhiteSpace(Id))
                {
                    throw new Exception("L'id est null");
                }

                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.QuerySingle<User>("user_get", param, commandType: CommandType.StoredProcedure);
                if (Helper.IsObjectNull(user))
                {
                    throw new Exception("Aucun utilisateur ne correspond à cette id");
                }
                return JsonSerializer.Serialize(new { Success = true, Content = user });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new{Success=false,Error =$"Une erreur est survenue:{e.Message}"});
            }
        }
        
        [HttpGet]
        public string GetAll()
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                var user = Connection.Query<User?>("user_get_all",commandType:CommandType.StoredProcedure);
                if (!user.Any())
                {
                    throw new Exception("Aucun utilisateur");
                }
                return JsonSerializer.Serialize(new { Success = true, Content= user });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = $"Une erreur est survenue : {e.Message}" });
            }
        }

        [HttpPost]
        public async Task<string> Register()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user==null)
                {
                    throw new Exception("Les données sont vides ou malformé");
                }

                if (string.IsNullOrWhiteSpace(user.FirstName) || string.IsNullOrWhiteSpace(user.LastName) || string.IsNullOrWhiteSpace(user.Email) ||
                    string.IsNullOrWhiteSpace(user.Phone) || string.IsNullOrWhiteSpace(user.Password) || string.IsNullOrWhiteSpace(user.Nationality))
                {
                    throw new Exception("Tout les champs sont requis");
                }

                user.Id = Guid.NewGuid().ToString();
                user.Admin = false;
                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_insert", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpPost]
        public async Task<string> Login()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var tempUser = JsonSerializer.Deserialize<User>(str);
                if (tempUser == null)
                {
                    throw new Exception("Les données sont vides ou malformé");
                }
                if (string.IsNullOrWhiteSpace(tempUser.Email))
                {
                    throw new Exception("La mail est requis");
                }
                if (string.IsNullOrWhiteSpace(tempUser.Password))
                {
                    throw new Exception("La mot de passe est requis");
                }
                DynamicParameters param = new();
                param.Add("Email", tempUser.Email);

                var user = Connection.QuerySingleOrDefault<User>("user_get_by_mail", param, commandType: CommandType.StoredProcedure);


                if (user==null || Helper.IsObjectNull(user))
                {
                    throw new Exception("Aucun utilisateur n'est associé a ce mail");
                }
                if (user.Password == tempUser.Password)
                {
                    return JsonSerializer.Serialize(new { Success = true, Content = user });
                }
                
                return JsonSerializer.Serialize(new { Success = false, Error = "Le mot de passe ne correspond pas" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public string Remove([FromQuery] string Id)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var row = Connection.Execute("user_delete", param, commandType: CommandType.StoredProcedure);
                if (row<1)
                {
                    return JsonSerializer.Serialize(new { Success = false, Error = "Aucun utilisateur ne correspond à cette id" });
                }
                return JsonSerializer.Serialize(new { Success = true });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        public async Task<string> Update()
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user.Id==null)
                {
                    throw new Exception("L'id est vide");
                }
                
                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_update", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public bool SetAdmin([FromQuery] string Id)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_set_admin", param, commandType: System.Data.CommandType.StoredProcedure);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }

        [HttpGet]
        public bool SetCustomer([FromQuery] string Id)
        {
            try
            {
                var IsHeaderSet = Request.Headers.TryGetValue("User-Id", out StringValues UserHeader);
                if (!IsHeaderSet)
                {
                    throw new Exception("Il faut être authentifier pour acceder à cette page");
                }
                if (!IsAdmin(UserHeader))
                {
                    throw new Exception("Il faut être un admin pour acceder à cette page");
                }
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_set_cutomer", param, commandType: CommandType.StoredProcedure);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }

        public bool IsAdmin([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.QuerySingle<User>("user_is_admin", param, commandType: CommandType.StoredProcedure);
                return user.Admin ?? false;
            }
            catch (Exception)
            {
                return false;
            }

        }
    }
}
