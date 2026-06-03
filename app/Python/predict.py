import sys
import json
import joblib
import pandas as pd
import warnings
warnings.filterwarnings("ignore")

def main():
    try:
        # Ambil path model dari argumen pertama (sys.argv[1])
        model_path = sys.argv[1]
        model = joblib.load(model_path)
        
        # Mengambil 9 skor dari argumen command line (mulai dari index 2)
        skor = [float(x) for x in sys.argv[2:11]]
        
        X = pd.DataFrame([skor], columns=[
            "logika", "sosial", "kreatif", "bisnis", "sains", 
            "komunikatif", "teliti", "empati", "kepemimpinan"
        ])
        
        probs = model.predict_proba(X)[0]
        
        hasil = []
        for i, kelas in enumerate(model.classes_):
            hasil.append({
                "jurusan": kelas,
                "score": round(probs[i] * 100, 2)
            })
            
        # Urutkan berdasarkan score tertinggi
        hasil = sorted(hasil, key=lambda d: d['score'], reverse=True)
        
        print(json.dumps(hasil))
        
    except Exception as e:
        # Jika error, kirim pesan error ini ke Laravel
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()