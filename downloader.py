import os
import urllib.request

# Konfigurasi Folder
save_dir = "public/img"
if not os.path.exists(save_dir):
    os.makedirs(save_dir)

# Header agar tidak diblokir Wikipedia (Menyamar jadi Browser)
opener = urllib.request.build_opener()
opener.addheaders = [('User-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36')]
urllib.request.install_opener(opener)

# Daftar URL Logo (Verified)
logos = {
    # --- PREMIER LEAGUE ---
    "arsenal.png": "https://upload.wikimedia.org/wikipedia/en/5/53/Arsenal_FC.svg",
    "avilla.png": "https://upload.wikimedia.org/wikipedia/en/f/f9/Aston_Villa_FC_crest_%282016%29.svg",
    "bournemouth.png": "https://upload.wikimedia.org/wikipedia/en/e/e5/AFC_Bournemouth_%282013%29.svg",
    "brentford.png": "https://upload.wikimedia.org/wikipedia/en/2/2a/Brentford_FC_crest.svg",
    "brighton.png": "https://upload.wikimedia.org/wikipedia/en/f/fd/Brighton_%26_Hove_Albion_logo.svg",
    "chelsea.png": "https://upload.wikimedia.org/wikipedia/en/c/cc/Chelsea_FC.svg",
    "cpalace.png": "https://upload.wikimedia.org/wikipedia/en/a/a2/Crystal_Palace_FC_logo_%282022%29.svg",
    "everton.png": "https://upload.wikimedia.org/wikipedia/en/7/7c/Everton_FC_logo.svg",
    "fulham.png": "https://upload.wikimedia.org/wikipedia/en/e/eb/Fulham_FC_%28shield%29.svg",
    "ipswich.png": "https://upload.wikimedia.org/wikipedia/en/4/43/Ipswich_Town.svg",
    "leicester.png": "https://upload.wikimedia.org/wikipedia/en/2/2d/Leicester_City_crest.svg",
    "liverpool.png": "https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg",
    "mancity.png": "https://upload.wikimedia.org/wikipedia/en/e/eb/Manchester_City_FC_badge.svg",
    "manutd.png": "https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg",
    "newcastle.png": "https://upload.wikimedia.org/wikipedia/en/5/56/Newcastle_United_Logo.svg",
    "nottingham.png": "https://upload.wikimedia.org/wikipedia/en/e/e5/Nottingham_Forest_F.C._logo.svg",
    "southampton.png": "https://upload.wikimedia.org/wikipedia/en/c/c9/FC_Southampton.svg",
    "spurs.png": "https://upload.wikimedia.org/wikipedia/en/b/b4/Tottenham_Hotspur.svg",
    "westham.png": "https://upload.wikimedia.org/wikipedia/en/c/c2/West_Ham_United_FC_logo.svg",
    "wolves.png": "https://upload.wikimedia.org/wikipedia/en/f/fc/Wolverhampton_Wanderers.svg",

    # --- LALIGA ---
    "madrid.png": "https://upload.wikimedia.org/wikipedia/en/5/56/Real_Madrid_CF.svg",
    "barca.png": "https://upload.wikimedia.org/wikipedia/en/4/47/FC_Barcelona_%28crest%29.svg",
    "atletico.png": "https://upload.wikimedia.org/wikipedia/en/f/f4/Atletico_Madrid_2017_logo.svg",
    "sevilla.png": "https://upload.wikimedia.org/wikipedia/en/3/3b/Sevilla_FC_logo.svg",
    "sociedad.png": "https://upload.wikimedia.org/wikipedia/en/f/f1/Real_Sociedad_logo.svg",
    "betis.png": "https://upload.wikimedia.org/wikipedia/en/1/13/Real_betis_logo.svg",
    "villarreal.png": "https://upload.wikimedia.org/wikipedia/en/7/70/Villarreal_CF_logo.svg",
    "bilbao.png": "https://upload.wikimedia.org/wikipedia/en/9/98/Club_Athletic_Bilbao_logo.svg",
    "valencia.png": "https://upload.wikimedia.org/wikipedia/en/c/ce/Valenciacf.svg",
    "girona.png": "https://upload.wikimedia.org/wikipedia/en/9/90/For_Girona_FC.svg",

    # --- SERIE A ---
    "inter.png": "https://upload.wikimedia.org/wikipedia/commons/0/05/FC_Internazionale_Milano_2021.svg",
    "milan.png": "https://upload.wikimedia.org/wikipedia/commons/d/d0/Logo_of_AC_Milan.svg",
    "juve.png": "https://upload.wikimedia.org/wikipedia/commons/b/bc/Juventus_FC_2017_icon_%28black%29.svg",
    "napoli.png": "https://upload.wikimedia.org/wikipedia/commons/2/2d/SSC_Napoli_2024_%28deep_blue_navy%29.svg",
    "roma.png": "https://upload.wikimedia.org/wikipedia/en/f/f7/AS_Roma_logo_%282017%29.svg",
    "lazio.png": "https://upload.wikimedia.org/wikipedia/en/c/ce/S.S._Lazio_badge.svg",
    "atalanta.png": "https://upload.wikimedia.org/wikipedia/en/6/66/AtalantaBC.svg",
    "fiorentina.png": "https://upload.wikimedia.org/wikipedia/commons/7/79/ACF_Fiorentina_2022_logo.svg",

    # --- BUNDESLIGA & LIGUE 1 ---
    "leverkusen.png": "https://upload.wikimedia.org/wikipedia/en/5/59/Bayer_04_Leverkusen_logo.svg",
    "bayern.png": "https://upload.wikimedia.org/wikipedia/commons/1/1b/FC_Bayern_M%C3%BCnchen_logo_%282017%29.svg",
    "dortmund.png": "https://upload.wikimedia.org/wikipedia/commons/6/67/Borussia_Dortmund_logo.svg",
    "leipzig.png": "https://upload.wikimedia.org/wikipedia/en/0/04/RB_Leipzig_2014_logo.svg",
    "stuttgart.png": "https://upload.wikimedia.org/wikipedia/commons/e/eb/VfB_Stuttgart_1893_Logo.svg",
    "psg.png": "https://upload.wikimedia.org/wikipedia/en/a/a7/Paris_Saint-Germain_F.C..svg",
    "monaco.png": "https://upload.wikimedia.org/wikipedia/en/b/ba/AS_Monaco_FC.svg",
    "marseille.png": "https://upload.wikimedia.org/wikipedia/en/8/86/Olympique_de_Marseille_logo.svg",
    "lille.png": "https://upload.wikimedia.org/wikipedia/en/6/6f/Lille_OSC_2018_logo.svg",
    "lyon.png": "https://upload.wikimedia.org/wikipedia/en/c/c6/Olympique_Lyonnais.svg"
}

print("--- MULAI DOWNLOAD ---")
for filename, url in logos.items():
    filepath = os.path.join(save_dir, filename)
    try:
        # Download file
        urllib.request.urlretrieve(url, filepath)
        
        # Cek ukuran file
        size = os.path.getsize(filepath)
        print(f"✅ {filename} SUKSES ({size} bytes)")
    except Exception as e:
        print(f"❌ {filename} GAGAL: {e}")

print("--- SELESAI ---")