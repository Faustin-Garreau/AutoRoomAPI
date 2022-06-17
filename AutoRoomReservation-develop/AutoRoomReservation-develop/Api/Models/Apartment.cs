using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Text;
using System.Text.Json.Serialization;
using System.Threading.Tasks;

namespace Models
{
    public class Apartment
    {

        [Key]
        [Column("id"), JsonPropertyName("id")]
        public string Id { get; set; }

        [Column("name"), JsonPropertyName("name")]
        public string Name { get; set; }

        [Column("street"), JsonPropertyName("street")]
        public string Street { get; set; }

        [Column("zipCode"), JsonPropertyName("zipCode")]
        public string ZipCode { get; set; }

        [Column("city"), JsonPropertyName("city")]
        public string City { get; set; }

        [Column("longitude")]
        public string Longitude { get; set; }

        [Column("latitude")]
        public string Latitude { get; set; }

        public List<Room> Rooms { get; set; } = new List<Room>();

    }
}
