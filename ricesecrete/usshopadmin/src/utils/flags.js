const flagsContext = require.context('@/assets/imgs/flags', false, /\.png$/);

const flags = flagsContext.keys().map(key => {
  const countryCode = key.replace('./', '').replace('.png', '');
  return {
    code: countryCode,
    name: countryCode.toUpperCase(),
    icon: flagsContext(key)
  };
});


export default flags;
