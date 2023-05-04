echo "Déploiement en cours..."

# Change to the directory where the code is
cd ~/fight_club/

# Pull changes from the live branch
git pull

# Shut down the existing containers
docker-compose down

# Build the image with the new changes
docker-compose build --no-cache

# Start the new containers
docker-compose up -d

echo "C'est déployé, gg!"